import clr
import os

clr.AddReference('..\\lib\\OpenHardwareMonitorLib')
from OpenHardwareMonitor.Hardware import Computer

computer = Computer()
computer.CPUEnabled = True
computer.HDDEnabled = True

computer.Open()

def get_hw_status():
    cpu_temp = []
    cpu_clock = []
    cpu_power = []
    hdd_temp = []
    hdd_used = []

    hdd = []
    
    for hardware in computer.Hardware:
        hardware.Update()  # Refresh the sensor data

        for sensor in hardware.Sensors:
            if sensor.Name == "CPU Package" and sensor.SensorType.ToString() == "Temperature":
                cpu_temp.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value, 2)} °C")

            if sensor.Name == "CPU Core #1" and sensor.SensorType.ToString() == "Clock":
                cpu_clock.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value/1000.0, 2)} GHz") 

            if sensor.Name == "CPU Package" and sensor.SensorType.ToString() == "Power":
                cpu_power.append(sensor.Value)
                # print(f"{sensor.Name} | Value: {round(sensor.Value, 2)} W")

            if sensor.Name == "Temperature":
                hdd_temp.append(sensor.Value)
                # print(f"HDD Temperature | Value: {round(sensor.Value, 2)} °C")
        
            if sensor.Name == "Used Space":
                hdd.append(sensor.Value)
                # print(f"HDD Used Space | Value: {round(sensor.Value, 0)}%")

    hdd_used.append(hdd[1])

    return cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used

cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used = get_hw_status()
print(cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used)




