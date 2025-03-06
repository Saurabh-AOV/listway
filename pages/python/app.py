import time
import os
import csv
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Start WebDriver
driver = webdriver.Chrome()
driver.get("https://web.whatsapp.com")

print("Please scan the QR code to log in.\n")
time.sleep(20)  # Allow time for manual login

# Load contacts from CSV
with open('C:/xampp/htdocs/listway-file/pages/python/contacts.csv', newline='', encoding='utf-8') as csvfile:
    readContacts = csv.reader(csvfile)
    
    for phone, msg, image_path in readContacts:
        print(f"📩 Sending to: {phone}, Message: {msg}, Image: {image_path}")

        # Open chat
        driver.get(f"https://web.whatsapp.com/send?phone={phone}")

        # Upload image if available
        if image_path.strip():
            if os.path.exists(image_path):
                print(f"✅ Image found: {image_path}")
                
                # Ensure this block is correctly indented   -   Working fine
                attach_btn = WebDriverWait(driver, 20).until(
                    EC.presence_of_element_located((By.XPATH, "//button[@title='Attach']"))
                )
                attach_btn.click()
                
                # Click on the photo/video upload button    -   Working fine
                image_input = WebDriverWait(driver, 5).until(
                    EC.presence_of_element_located((By.XPATH, "//input[@accept='image/*,video/mp4,video/3gpp,video/quicktime']"))
                )
                image_input.send_keys(image_path)  # Attach image

                time.sleep(2)  # Small delay

                try:
                    send_button = WebDriverWait(driver, 2).until(
                        EC.presence_of_element_located((By.XPATH, "//span[@class='xsgj6o6']"))
                    )
                    send_button.click()
                    print("✅ Send button clicked successfully!")
                except Exception as e:
                    print(f"❌ Send button error: {e}")

                print(f"✅ Image sent to {phone}")
            else:
                print(f"❌ ERROR: Image file not found at {image_path}")

# Close the browser
driver.quit()
