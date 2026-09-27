# SecondInnings50 — Member Onboarding & Admin Vetting Manual

Welcome to the **SecondInnings50** member onboarding and administration portal. This guide outlines how applicants register, how the verification safety gating behaves, how members connect, and how the platform administrator verifies accounts and reviews logs.

---

## 1. Member Onboarding & Registration (Incognito/Member view)

To test the applicant experience, open an **Incognito window** (so you are logged out of the admin panel) and follow these steps:

1. **Visit the Registration Page**:
   - Go to your website's join page: **`/join-application/`** (or `/join/`).
   
2. **Submit Application Details**:
   - Fill out the forms (Full Name, Date of Birth, Gender, Occupation, Marital Status, City/State, Circle Interests, and Travel Preferences).
   - Enter your email address and **WhatsApp mobile number**.
   - Set a **secure account password** at the bottom of the contact block.
   
3. **Simulated OTP Verification overlay**:
   - Click the submit button. 
   - A premium **verification window** will pop up prompting you for a 4-digit code sent to your WhatsApp number.
   - For validation testing, **type any 4-digit numeric code** (e.g. `1234`) and click **Verify & Submit**.
   
4. **Vetting Access Gating**:
   - Once verified, the page automatically logs you in and redirects you to the member directory: **`/directory/`**.
   - Because your account has just been created, your vetting status is `pending_review`.
   - You will see a warning notice: 
     > *"Your membership application is securely received. Access will be unlocked following your standard manual verification call."*
   - Verify that other member profiles and search bars are hidden until your account gets approved.

---

## 2. Directory Navigation & Connect requests (Approved Member view)

Once your account has been approved by the admin (see section 3), refresh the **`/directory/`** page:

1. **Member lookup & filters**:
   - You can now see cards of all other approved members on the platform.
   - Use the **Filter Sidebar** on the left to filter members by **Gender**, **Age Bracket**, **City/Location**, and **Connection Focus** (Friendship, Companionship, etc.).
   
2. **Secure Privacy Gating**:
   - Notice that personal contact information (email address and WhatsApp phone number) is completely hidden on other member cards.
   
3. **Connect Handshake Interaction**:
   - Click the **`Send Connect Request`** button on any card.
   - The button text updates to **`⏱ Connection Requested`** and locks.
   - **Approved Connection**: Once the other member accepts your request (or when the admin changes the handshake status to approved), the profile card will reveal their **Email**, **Phone number**, and a green **Message on WhatsApp** button which connects you directly to their chat.

---

## 3. Administrative Vetting & Tracker Logs (Admin view)

Log in to your WordPress dashboard (`/wp-admin/`) using the **Administrator** account:

1. **SI50 Onboarding Vetting Panel**:
   - In the left sidebar, click the new **`SI50 Onboarding`** menu option (flagged with a card identity icon).
   
2. **Approve or Reject Members**:
   - The left column (**Registered Onboarding Applications**) lists all registered users.
   - Your newly created user will have a yellow badge stating **"Pending Vetting"**.
   - Review their profile details (age, occupation, emergency contact).
   - Perform your manual verification call, then click the green **`Approve`** button. The user status will toggle to green **"Approved"** and their directory access will unlock.
   
3. **Connection Handshake tracker**:
   - The right column (**Connection Request Tracker**) lists all social interaction logs on the website.
   - Review the log to track:
     * **Sender Name** (who clicked to connect)
     * **Receiver Name** (who received the invite)
     * **Connection Status** (Pending, Approved, or Declined)
     * **Date & Time**

---

## 4. SMTP Email setup for Live/Staging Servers

WordPress defaults to using standard PHP `mail()` to send notification emails. Many modern hosts (including TasteWP free servers or GoDaddy shared environments) block PHP mail to protect against spam.

To receive the registration notification emails in your inbox, install an SMTP configuration plugin:
1. Go to **Plugins > Add New** inside the WordPress dashboard.
2. Search for **WP Mail SMTP** or **Easy WP SMTP** and click install.
3. Follow the plugin wizard to connect it to your domain mail server (SMTP), Gmail, or Outlook.
4. Once connected, all registration alert emails will route directly to your administrator inbox.
