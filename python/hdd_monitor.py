# from connection import con
# from dt import now
# from monitor import get_hw_status


class HDDMonitor:
    def __init__(self, con, now, hdd_temp, hdd_used, pc_name):
        self.con = con
        self.now = now
        self.hdd_temp = hdd_temp
        self.hdd_used = hdd_used
        self.pc_name = pc_name

    def insert_hdd_temp(self):
        _con = self.con
        _now = self.now 
        _hdd_temp = self.hdd_temp
        _pc = self.pc_name

        mycursor = _con.cursor()

        sql = "INSERT INTO hdd_status (date_time, hdd_value, hdd_sensor, comp_name) VALUES (%s, %s, %s, %s)"
        val_hdd =  (_now, _hdd_temp[0], "temperature", _pc)
        
        mycursor.execute(sql, val_hdd)

        _con.commit()

    def insert_hdd_used(self):
        _con = self.con
        _now = self.now 
        _hdd_used = self.hdd_used
        _pc = self.pc_name

        mycursor = _con.cursor()

        sql = "INSERT INTO hdd_status (date_time, hdd_value, hdd_sensor, comp_name) VALUES (%s, %s, %s, %s)"
        val_hdd =  (_now, _hdd_used[0], "used", _pc)
        
        mycursor.execute(sql, val_hdd)

        _con.commit()    


# cpu_temp, cpu_clock, cpu_power, hdd_temp, hdd_used = get_hw_status()

# hdd_monitor = HDDMonitor(con, now, hdd_temp, hdd_used, "PC 1")

# hdd_monitor.insert_hdd_temp()
# hdd_monitor.insert_hdd_used()

# print("CPU Monitoring...")

# while 1:
#     cpu_monitor.insert_cpu_temp()
#     cpu_monitor.insert_cpu_clock()
#     cpu_monitor.insert_cpu_power()

#     time.sleep(5)