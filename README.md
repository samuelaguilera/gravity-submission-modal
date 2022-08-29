**THIS ADD-ON IS ONLY FOR DEMONSTRATION --- IT HASN'T BEEN TESTED IN LIVE SITES --- USE IT AT YOUR OWN RISK**

# Description

This is an example of how to implement [jQuery Modal](https://jquerymodal.com/) for Gravity Forms to show a "Please wait..." modal after clicking the submit/next/previous buttons in forms **not** using ajax submission.

While it has been proved to work fine out of the box in a WordPress default testing installation, it hasn't been tested in live sites. If you plan to use the add-on as is, be sure to test it before using the add-on in a live site.

# Usage

- Install and activate from your site Plugins page.
- Go to Submission Modal under your form settings page, click the Enable Submission Modal option and save settings.

# How It Works

For forms where the setting has been enabled, the add-on injects a very simple HTML markup after the form that would be used to show the modal and spinner, also enqueues the jQuery Modal script and CSS files and appends a call to jQuery Modal in the submit button.

When the user clicks the submit/next/previous button, a full screen modal with a spinner and the text "Please wait..." is shown, the user can't close it, so the modal remains visible until one of the following happens:

- The page loads with the confirmation text or next/previous form page (for multi page forms).
- The user is redirected if you're using a Page/Redirect confirmation or add-on doing redirection to a third-party site (e.g. PayPal Standard).
- The page loads with a validation error (e.g. Required field was left empty).

# Limitations

Not intended for forms using ajax submission or requiring user interaction without page reload after clicking the submit button (e.g. PayPal Checkout, Stripe asking for 3D Secure validation, or any other add-on showing a popup after clicking the submit button).