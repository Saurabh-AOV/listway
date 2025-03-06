from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import csv
import os
import sys

# Force UTF-8 Encoding
sys.stdout.reconfigure(encoding='utf-8')

# Default delay value
DELAY_BETWEEN_MESSAGES = int(sys.argv[1])

# Open chrome browser
driver = webdriver.Chrome()

baseurl = "https://web.whatsapp.com"
driver.get(baseurl)

# Wait for QR Code scan (increase sleep time if needed)
print("Please scan the QR code to log in to WhatsApp Web.\n")
time.sleep(20)      # Wait for the 20 seconds for scanning

# Read contacts and messages from the CSV file
with open('C:/xampp/htdocs/listway-file/pages/python/contacts.csv', newline='') as csvfile:

    readContacts = csv.reader(csvfile)
        
    for phone, msg in readContacts:
        phonnum = phone
        message = msg

        # Construct the WhatsApp URL for the contact
        sameTab = (baseurl + '/send?phone=' + str(phonnum))
        driver.get (sameTab)
        time.sleep (8)     # Give time for the chat to load

        try:
            # Find the message input field and send the message
            content = driver.switch_to.active_element

            # Your message written in the input box of the chat
            content.send_keys(message)

            #Pressing enter key in whatsapp
            content.send_keys(Keys.RETURN)

            time.sleep(1)

            # Check if the message is sent successfully
            last_message = driver.find_elements(By.XPATH, "//div[contains(@class, 'message-out')]")
            if last_message:
                print(f"\u2705 Message sent successfully to {phonnum}")
            else:
                print(f"\u26a0 Message might not be sent to {phonnum}")

            
        except Exception as e:
            print(f"\u274c Message NOT sent to {phonnum}. Error: {str(e)}")

        # Wait before sending the next message
        time.sleep(DELAY_BETWEEN_MESSAGES)