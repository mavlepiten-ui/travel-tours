# How to Connect Your Website to GetYourGuide

Connecting your website to GetYourGuide (GYG) allows you to earn commissions and provide your customers with professional booking options for tours and activities.

## 1. Anatomy of a GetYourGuide Link

Your customized link is:
`https://www.getyourguide.com/-l101043/?partner_id=CD951&...`

### Key Components:
- **`-l101043/`**: This is the Location ID for Tagaytay.
- **`partner_id=CD951`**: This is your unique ID. **Never remove this**, as it tracks your referrals.

---

## 2. Using a "Simple Button" (Step-by-Step)

This is the most reliable way to connect. You add a button that leads directly to your GetYourGuide landing page.

### Example HTML Code:
```html
<a href="YOUR_LINK_HERE" class="btn btn-primary" target="_blank" rel="noopener">
    Book on GetYourGuide
</a>
```

### Why use `target="_blank"` and `rel="noopener"`?
- `target="_blank"`: Opens the link in a new tab so the user doesn't lose your website.
- `rel="noopener"`: A security best practice when opening new tabs.

---

## 3. Using a "Widget" (The Advanced Way)

GetYourGuide provides widgets that show actual tours on your site.

### The Code Snippet:
To show tours in Tagaytay, you would use this code:

```html
<!-- The Widget Container -->
<div 
  data-gyg-href="https://www.getyourguide.com/tagaytay-l101043/" 
  data-gyg-location-id="101043" 
  data-gyg-locale-code="en-US" 
  data-gyg-widget="activities" 
  data-gyg-number-of-items="3" 
  data-gyg-partner-id="CD951">
</div>

<!-- The Essential Script (Add this once at the bottom of your page) -->
<script async src="https://widget.getyourguide.com/dist/pa.umd.production.min.js"></script>
```

---

## 4. Best Practices for Placement

1.  **Services Page**: Add a "Check Availability" button to each tour you offer that redirects to the corresponding GYG page.
2.  **Home Page**: Add a "Recommended Tours" section using the Widget.
3.  **Footer**: Add a small "Official Partner of GetYourGuide" badge to build trust.
