# Energy savings techniques in a Smart City Environment - Smart Street Lighting

<p>Road lights are perhaps the largest consumer of energy in cities. So in order to save energy in smart cities, it is proposed in this project the design of an intelligent road lighting system based on LED lamps and information and wireless communication technologies. This intelligent street lighting system can detect daylight and traffic on the road and differentiate the intensity of LED lamps according to traffic flow.</p>

<p>This system consists of three LED lamps, a Raspberry Pi 3, which operates as a web server, three Zolertia Re-Motes, which are responsible for the communication between the three street lamps, and between the lamps and the Raspberry Pi, and four potentiometers, of which three are used instead of motion sensors and the fourth is used instead of a light sensor. In order for Zolertia Re-Motes to communicate with each other and in addition to illuminate the lamp to which they are connected at the desired brightness, we upload the appropriate Contiki code to them. Contiki is the operating system of the RE-Motes, and is implemented in C.</p>

<p>Each one of the three lamps on our system is equipped with a Zolertia Re-Mote. The potentiometers acting as the motion sensor are connected to all three Zolertia Re-Motes and therefore to each lamp. The potentiometer operating as a light sensor is connected just to one Re-Mote (thus only to one lamp), which is the central node. The value of this last potentiometer, which will essentially indicate the brightness of the area, is sent wirelessly via the Zolertia Re-Motes to the other two lamps. The central Re-Mote is also connected to the Raspberry Pi 3, which collects information about the status of each lamp and stores them in a database. The CC2538 ARM Cortex-M3 microcontroller built into the Zolertia Re-Mote platform performs the control actions (whereas the LED will light up and at what brightness) based on the values it receives from the potentiometers.  When we want the lamp's brightness to be 50%, the microcontroller produces a PWM signal that is fed to the LED lamp, which changes its operating cycles and radiates with lower intensity.</p>

<p>The 802.15.4 standard, which is supported by the Zolertia Re-Mote, is used for the communication between the lamps. Also, with the Rime stack of the Contiki OS, we achieve multi-hop communication between the nodes. In our system, the packets exchanged between the nodes are: a) the value of the brightness of the area, which is sent from the central node to the other two nodes, and b) the state of each node, which is sent from nodes two and three to the central node.</p>

<p>Depending on the brightness of the area, we distinguish the following three cases:
  <ol>
    <li>The brightness of the area is high (e.g. a sunny day) and therefore the lights remain off regardless of whether there is cars passing or not.</li>
    <li>The brightness of the area is neither too high nor too low (e.g. a cloudy day), so the lights remain off when there is no car passing and light up to 50% of their brightness when there is a vehicle or pedestrian traffic.</li>
    <li>The brightness of the area is low (e.g. at night), so the lights remain lit at 50% of their brightness when there is no traffic and turn on 100% when the motion sensor senses a vehicle or pedestrian.</li>
  </ol>
</p>

<p>As mentioned above, the information regarding the status of each node is stored in a database via the Raspberry Pi 3. In order to do that, we implemented a Python program that runs on the Raspberry Pi, which communicates serially with the Zolertia Re-Mote that is the central node, and reads the data and stores them in the database. So, by monitoring this database we can see the status of each node (lamp) any time we want.</p>

<p>Finally, since the constant monitoring of the database on phpMyAdmin is not the ideal way to see the status of the nodes, we set up some web pages that are linked to the database. Through these web pages we can see the status of each node at any time, as well as some statistics about the energy consumption of each node.</p>
