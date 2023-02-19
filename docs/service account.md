# Configuring Service Account
This process requires a lot of little steps. Follow them and you should be all set!

## Step 1: Get a google cloud account
You'll need a google cloud account to get a service account. You can get one [here](https://console.cloud.google.com/).

## Step 2: Create a project
You'll need to create a project in your google cloud account. You can do that [here](https://console.cloud.google.com/projectcreate).
![service-account-step-1.PNG](images%2Fservice-account-step-1.PNG)

## Step 3: Enable Google Analytics Data API
You'll need to enable the Google Analytics Data API for your project. You can do that [here](https://console.cloud.google.com/apis/library/analyticsdata.googleapis.com).
![service-account-step-2.PNG](images%2Fservice-account-step-2.PNG)
![service-account-step-3.PNG](images%2Fservice-account-step-3.PNG)

## Step 4: Create Service Account At APIs & Services, Credentials
You'll need to create a service account for your project. You can do that [here](https://console.cloud.google.com/apis/credentials).

![service-account-step-4.PNG](images%2Fservice-account-step-4.PNG)
## Step 5: Create credentials for your service account
Once you're on this screen, click create credentials and select service account. Name it anything you want, you can skip steps 2 and 3 (don't use this for anything else, or enable any other API).
![service-account-step-5.PNG](images%2Fservice-account-step-5.PNG)

## Step 6: Select the Service account you created at the bottom of the page

## Step 7: Select "Keys" at the top of the screen, then add key
![service-account-step-6.PNG](images%2Fservice-account-step-6.PNG)

## Step 8: Generate a new key and select JSON
![service-account-step-7.PNG](images%2Fservice-account-step-7.PNG)

## Step 9: Go to your Google Analytics v4 property and add the service account as a user
Use the email that was given by the service account creation process (same one that appears in the JSON file).
![service-account-step-8.PNG](images%2Fservice-account-step-8.PNG)
