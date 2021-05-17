from win32 import win32print
import requests
import json
import time
printers = win32print.EnumPrinters(5)
#print (printers)


def setting_printer():
    url="http://omkarhomoeohall.co.in/cron/get_printer_name"
    headers={"Content-Type": "application/json", "Accept": "application/json"}
    printer_computer_id=3
    post_data = json.dumps({ "printer_computer_id": printer_computer_id})
    r = requests.post(url, data=post_data, headers=headers)
    json_to_dictionary = json.loads(r.text)   
    #print(json_to_dictionary['led_value'])
    #results = r.json()
    #print (results)
    return json_to_dictionary['selected_printer_name']

    

while True:  
 printer_name=setting_printer()
 if len(printer_name) > 0 :
  win32print.SetDefaultPrinter(printer_name)
  #print (return_val)
  time.sleep(0.5)

