"""
This is uploaded to the Raspberry Pi, which communicates serially with the Zolertia Re-Mote that is the central node. It reads and stores data in a database.
"""

#!/usr/bin/python
import serial
import time
import MySQLdb as mdb

from warnings import filterwarnings
filterwarnings('ignore', category = mdb.Warning)

# serial port configuration for communication with Zolertia Re-Mote
ser = serial.Serial(
   port='/dev/ttyUSB0',\
   baudrate=115200,\
   parity=serial.PARITY_NONE,\
   stopbits=serial.STOPBITS_ONE,\
   bytesize=serial.EIGHTBITS,\
   timeout=0)

# connect to smart_street_light database
con = mdb.connect('127.0.0.1', 'user_name', 'password', 'smart_street_light');

while True:
	line = ser.readline();

	if line and (len(line) <= 6):
		words = line.split()
		# date
        dt = time.strftime("%d/%m/%Y")
		# time
		tm = time.strftime("%X")
		
		if words[1] == "'3'":
			words[1] = "Full On"
			a = 100
		elif words[1] == "'2'":
			words[1] = "50% Night"
			a = 50
		elif words[1] == "'1'":
			words[1] = "50% Day"
			a = 50
		else:
			words[1] = "Off"
			a = 0

		# Create table StreetLights (if it does not exist) and 
		# register date, time, node number, and node state.
		with con:
    
    			cur = con.cursor()
    			cur.execute("CREATE TABLE IF NOT EXISTS StreetLights (Id INT PRIMARY KEY AUTO_INCREMENT, \
				Date VARCHAR(15), \
				Time VARCHAR(15), \
                		Node INT,\
				State VARCHAR(15), \
				StateValue INT)")
    			cur.execute("INSERT INTO StreetLights (Date, Time, Node, State, StateValue) VALUES (%s, %s, %s, %s, %s)", (dt, tm, words[0], words[1], a))
		
ser.close()



