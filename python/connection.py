import mysql.connector

def connect():
  con = mysql.connector.connect(
    # local database
    host="localhost",
    user="root",
    password="",
    database="uhm"

    # remote database
    # host="172.16.35.127",
    # user="capstone",
    # password="capstone",
    # database="capstone"
  )
  
  return con

# con = connect()

# mycursor = con.cursor()

# sql = "INSERT INTO `test`(`message`) VALUES (%s)"
# val_cpu_temp =  ['database']
# mycursor.execute(sql, val_cpu_temp)

# con.commit()

# print(mycursor.rowcount, "record inserted")

# test if connected to the database
# print(connect().is_connected())