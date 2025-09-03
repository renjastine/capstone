# from connection import con
# from dt import now
# from monitor import get_hw_status

class CpuMonitor:
    def __init__(self, con, now, cpu_temp, cpu_clock, cpu_power, pc_name):
        self.con = con
        self.now = now
        self.cpu_temp = cpu_temp
        self.cpu_clock = cpu_clock
        self.cpu_power = cpu_power
        self.pc_name = pc_name

    def insert_cpu_temp(self):
        _now = self.now 
        _cpu_temp = self.cpu_temp
        _con = self.con
        _pc = self.pc_name

        mycursor = _con.cursor()

        sql = "INSERT INTO cpu_status (date_time, cpu_value, cpu_sensor, comp_name) VALUES (%s, %s, %s, %s)"
        val_cpu_temp =  (_now, _cpu_temp[0], "temperature", _pc)
        
        mycursor.execute(sql, val_cpu_temp)

        _con.commit()

    def insert_cpu_clock(self):
        _now = self.now 
        _cpu_clock = self.cpu_clock
        _con = self.con
        _pc = self.pc_name

        mycursor = _con.cursor()

        sql = "INSERT INTO cpu_status (date_time, cpu_value, cpu_sensor, comp_name) VALUES (%s, %s, %s, %s)"
        val_cpu_clock =  (_now, _cpu_clock[0], "clock", _pc)
        
        mycursor.execute(sql, val_cpu_clock)

        _con.commit()

    def insert_cpu_power(self):
        _now = self.now 
        _cpu_power = self.cpu_power
        _con = self.con
        _pc = self.pc_name

        mycursor = _con.cursor()

        sql = "INSERT INTO cpu_status (date_time, cpu_value, cpu_sensor, comp_name) VALUES (%s, %s, %s, %s)"
        val_cpu_power =  (_now, _cpu_power[0], "power", _pc)
        
        mycursor.execute(sql, val_cpu_power)

        _con.commit()
    


# cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used = get_hw_status()

# cpu_monitor = CpuMonitor(con, now, cpu_temp, cpu_clock, cpu_power, "PC 1")

# print("CPU Monitoring...")

# while 1:
#     cpu_monitor.insert_cpu_temp()
#     cpu_monitor.insert_cpu_clock()
#     cpu_monitor.insert_cpu_power()

#     time.sleep(5)