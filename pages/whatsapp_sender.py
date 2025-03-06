import time
import sys
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options

def send_whatsapp_message(contacts, message, image_path, timer):
    # Set up Chrome options
    chrome_options = Options()
    chrome_options.add_argument("--user-data-dir=/home2/apnaonli/.config/google-chrome")
    chrome_options.add_argument("--profile-directory=Default")
    
    # Set up the Chrome driver
    service = Service('/home2/apnaonli/public_html/listway/pages/chromedriver/chromedriver')
    driver = webdriver.Chrome(service=service, options=chrome_options)
    
    driver.get('https://web.whatsapp.com')
    input("Scan the QR code and press Enter to continue...")

    for contact in contacts:
        try:
            search_box = driver.find_element(By.XPATH, '//div[@contenteditable="true"][@data-tab="3"]')
            search_box.clear()
            search_box.send_keys(contact)
            search_box.send_keys(Keys.ENTER)
            time.sleep(2)
            
            if message:
                message_box = driver.find_element(By.XPATH, '//div[@contenteditable="true"][@data-tab="6"]')
                message_box.send_keys(message)
                message_box.send_keys(Keys.ENTER)
                time.sleep(timer)
            
            if image_path:
                attachment_box = driver.find_element(By.CSS_SELECTOR, 'span[data-icon="clip"]')
                attachment_box.click()
                time.sleep(1)
                
                image_box = driver.find_element(By.CSS_SELECTOR, 'input[type="file"]')
                image_box.send_keys(image_path)
                time.sleep(2)
                
                send_button = driver.find_element(By.XPATH, '//span[@data-icon="send"]')
                send_button.click()
                time.sleep(timer)
        
        except Exception as e:
            print(f"Failed to send message to {contact}: {str(e)}")
    
    driver.quit()

if __name__ == '__main__':
    contacts_str = sys.argv[1]
    message = sys.argv[2]
    image_path = sys.argv[3]
    timer = int(sys.argv[4])
    
    contacts = contacts_str.split(',')
    
    send_whatsapp_message(contacts, message, image_path, timer)
