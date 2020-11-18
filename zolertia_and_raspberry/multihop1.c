/* This is uploaded to central node */


#include "contiki.h"
#include <stdio.h>
#include "dev/leds.h"

#include "net/rime/rime.h"

#include "lib/list.h"
#include "lib/memb.h"
#include "lib/random.h"

/* Headers for sensors*/
#include "lib/sensors.h"
#include "dev/adc-zoul.h"
#include "dev/zoul-sensors.h"

/* Headers for pwm method*/
#include "cpu.h"
#include "dev/watchdog.h"
#include "dev/sys-ctrl.h"
#include "pwm.h"
#include "lpm.h"
#include "dev/ioc.h"


typedef struct {
  uint8_t timer;
  uint8_t ab;
  uint8_t port;
  uint8_t pin;
  uint8_t duty;
  uint8_t off_state;
  uint32_t freq;
} pwm_config_t;


static pwm_config_t pwm_num = {
    .timer = PWM_TIMER_1,
    .ab = PWM_TIMER_A,
    .port = GPIO_D_NUM,
    .pin = 5,
    .duty = 50,
    .freq = 100,
    .off_state = PWM_OFF_WHEN_STOP,
};


#define CHANNEL 135


struct example_neighbor {
  struct example_neighbor *next;
  linkaddr_t addr;
  struct ctimer ctimer;
};

#define NEIGHBOR_TIMEOUT 3600 * CLOCK_SECOND
#define MAX_NEIGHBORS 16
LIST(neighbor_table);
MEMB(neighbor_mem, struct example_neighbor, MAX_NEIGHBORS);
/*---------------------------------------------------------------------------*/
static struct etimer et;
/*---------------------------------------------------------------------------*/
PROCESS(multihop_process, "multihop process");
AUTOSTART_PROCESSES(&multihop_process);
/*---------------------------------------------------------------------------*/
/*
 * This function is called by the ctimer present in each neighbor
 * table entry. The function removes the neighbor from the table
 * because it has become too old.
 */
static void
remove_neighbor(void *n)
{
  struct example_neighbor *e = n;

  list_remove(neighbor_table, e);
  memb_free(&neighbor_mem, e);
}
/*---------------------------------------------------------------------------*/
/*
 * This function is called when an incoming announcement arrives. The
 * function checks the neighbor table to see if the neighbor is
 * already present in the list. If the neighbor is not present in the
 * list, a new neighbor table entry is allocated and is added to the
 * neighbor table.
 */
static void
received_announcement(struct announcement *a,
                      const linkaddr_t *from,
		      uint16_t id, uint16_t value)
{
  struct example_neighbor *e;

  /*  printf("Got announcement from %d.%d, id %d, value %d\n",
      from->u8[0], from->u8[1], id, value);*/

  /* We received an announcement from a neighbor so we need to update
     the neighbor list, or add a new entry to the table. */
  for(e = list_head(neighbor_table); e != NULL; e = e->next) {
    if(linkaddr_cmp(from, &e->addr)) {
      /* Our neighbor was found, so we update the timeout. */
      ctimer_set(&e->ctimer, NEIGHBOR_TIMEOUT, remove_neighbor, e);
      return;
    }
  }

  /* The neighbor was not found in the list, so we add a new entry by
     allocating memory from the neighbor_mem pool, fill in the
     necessary fields, and add it to the list. */
  e = memb_alloc(&neighbor_mem);
  if(e != NULL) {
    linkaddr_copy(&e->addr, from);
    list_add(neighbor_table, e);
    ctimer_set(&e->ctimer, NEIGHBOR_TIMEOUT, remove_neighbor, e);
  }
}
static struct announcement example_announcement;
/*---------------------------------------------------------------------------*/
/*
 * This function is called at the final recepient of the message.
 */
static void
recv(struct multihop_conn *c, const linkaddr_t *sender,
     const linkaddr_t *prevhop,
     uint8_t hops)
{
    printf("%d '%s'\n", sender->u8[1], (char *)packetbuf_dataptr());
}
/*
 * This function is called to forward a packet. The function picks a
 * random neighbor from the neighbor list and returns its address. The
 * multihop layer sends the packet to this address. If no neighbor is
 * found, the function returns NULL to signal to the multihop layer
 * that the packet should be dropped.
 */
static linkaddr_t *
forward(struct multihop_conn *c,
	const linkaddr_t *originator, const linkaddr_t *dest,
	const linkaddr_t *prevhop, uint8_t hops)
{
  /* Find a random neighbor to send to. */
  int num, i;
  struct example_neighbor *n;

  if(list_length(neighbor_table) > 0) {
    num = random_rand() % list_length(neighbor_table);
    i = 0;
    for(n = list_head(neighbor_table); n != NULL && i != num; n = n->next) {
      ++i;
    }
    if(n != NULL) {
      /*printf("%d.%d: Forwarding packet to %d.%d (%d in list), hops %d\n",
	     linkaddr_node_addr.u8[0], linkaddr_node_addr.u8[1],
	     n->addr.u8[0], n->addr.u8[1], num,
	     packetbuf_attr(PACKETBUF_ATTR_HOPS));*/
      return &n->addr;
    }
  }
  printf("%d.%d: did not find a neighbor to foward to\n",
	 linkaddr_node_addr.u8[0], linkaddr_node_addr.u8[1]);
  return NULL;
}
static const struct multihop_callbacks multihop_call = {recv, forward};
static struct multihop_conn multihop;
/*---------------------------------------------------------------------------*/
PROCESS_THREAD(multihop_process, ev, data)
{	
  PROCESS_EXITHANDLER(multihop_close(&multihop_process);) 
  PROCESS_BEGIN();
  
  /* Initialize the memory for the neighbor table entries. */
  memb_init(&neighbor_mem);

  /* Initialize the list used for the neighbor table. */
  list_init(neighbor_table);

  /* Open a multihop connection on Rime channel CHANNEL. */
  multihop_open(&multihop, CHANNEL, &multihop_call);

  /* Register an announcement with the same announcement ID as the
     Rime channel we use to open the multihop connection above. */
  announcement_register(&example_announcement,
			CHANNEL,
			received_announcement);

  /* Set a dummy value to start sending out announcments. */
  announcement_set_value(&example_announcement, 0);

  /* The masks below converts the Port number and Pin number to base and mask values */
  #define EXAMPLE_PORT_BASE  GPIO_PORT_TO_BASE(GPIO_D_NUM)
  #define EXAMPLE_PIN_MASK   GPIO_PIN_MASK(5)

  /* The ADC zoul library configures the on-board enabled ADC channels*/
  adc_zoul.configure(SENSORS_HW_INIT, ZOUL_SENSORS_ADC_ALL);

  /* Set timer*/
  etimer_set(&et, CLOCK_SECOND*2);

  static int light;
  static int motion;

  /* Loop forever */
  while(1) {
    linkaddr_t two;
    linkaddr_t three;

    PROCESS_WAIT_EVENT_UNTIL(etimer_expired(&et));

    light = adc_zoul.value(ZOUL_SENSORS_ADC1); // Measure brightness
    motion = adc_zoul.value(ZOUL_SENSORS_ADC3); // Measure traffic
    //printf("Light: %d\n", light);
    //printf("Motion: %d\n", motion);

	// Send to the first node
	// Copy light value at packet buffer
    packetbuf_copyfrom(&light, sizeof(light));

	// Rime address of final receiver
    two.u8[0] = 0;
    two.u8[1] = 2;

	// Send the packet
    multihop_send(&multihop, &two);

	// Send to the second node
	// Copy light value to packet buffer
	packetbuf_copyfrom(&light, sizeof(light));

	// Rime address of final receiver
    three.u8[0] = 0;
    three.u8[1] = 3;

	// Send the packet
    multihop_send(&multihop, &three);

   // Check if it is night
    if (light >= 16500) { 
	// If there is traffic, turn on the lights at full brightness
	if (motion > 9000) { 
		GPIO_SOFTWARE_CONTROL(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
	  	GPIO_SET_OUTPUT(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
		GPIO_SET_PIN(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK); /*Set High*/
		printf("%d '3'\n", linkaddr_node_addr.u8[1]);
	     }
	// If there is not traffic, turn on lights at 50% brightness
	else {
		pwm_enable(pwm_num.freq, pwm_num.duty, 0, pwm_num.timer, pwm_num.ab);
		pwm_start(pwm_num.timer, pwm_num.ab, pwm_num.port, pwm_num.pin);
		printf("%d '2'\n", linkaddr_node_addr.u8[1]);

	     }
	}
    // Check if it is a cloudy day
    else if (light < 16500 && light >= 6600) {
	// If there is traffic, turn on lights at 50% brightness
	if(motion > 9000) {
		pwm_enable(pwm_num.freq, pwm_num.duty, 0, pwm_num.timer, pwm_num.ab);
		pwm_start(pwm_num.timer, pwm_num.ab, pwm_num.port, pwm_num.pin);
		printf("%d '1'\n", linkaddr_node_addr.u8[1]);
	   }
	// If there is not traffic, turn off the lights
	else {
		GPIO_SOFTWARE_CONTROL(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
	 	GPIO_SET_OUTPUT(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
		GPIO_CLR_PIN(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK); /*Set Low*/
		printf("%d '0'\n", linkaddr_node_addr.u8[1]);
	   }
     }
    // if it is day, the lights are turned off
    else { 
 	GPIO_SOFTWARE_CONTROL(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
 	GPIO_SET_OUTPUT(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK);
	GPIO_CLR_PIN(EXAMPLE_PORT_BASE, EXAMPLE_PIN_MASK); /*Set Low*/
	printf("%d '0'\n", linkaddr_node_addr.u8[1]);
	}

   
   /*Reset timer */
   etimer_reset(&et);
  }

  PROCESS_END();
}
/*---------------------------------------------------------------------------*/
