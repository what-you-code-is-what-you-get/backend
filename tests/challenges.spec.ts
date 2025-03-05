import { test, expect } from '@playwright/test';
const { exec } = require('child_process');
const { chromium } = require('playwright');

test.use({ 
  browserName: 'chromium',
  launchOptions: {
    ignoreHTTPSErrors: true,
  }
});

// Function to execute the drush command and get the URL
function getDrushUrl(): Promise<string> {
  return new Promise((resolve, reject) => {
    exec('ddev drush uli', (err, stdout, stderr) => {
      if (err) {
        reject(`Error running drush: ${err.message}`);
        return;
      }
      if (stderr) {
        reject(`stderr: ${stderr}`);
        return;
      }

      resolve(stdout.trim()); // Return the URL
    });
  });
}

// Main test
test('can create challenge, submission and then delete', async ({ page }) => {
  const loginUrl: string | null = await getDrushUrl(); // Get the login URL
  const url: URL = new URL(loginUrl);
  url.protocol = 'https'; // Change the protocol to https
  const baseUrl: string = `${url.protocol}//${url.host}`;

  // Step 1: Visit the URL
  await page.goto(loginUrl);

  await page.goto(baseUrl + '/node/add/challenge');

  // Fill input title[0][value] with "Challenge time!"
  await page.fill('input[name="title[0][value]"]', 'Challenge time!');

  // Click input with type submit and the text "Add media"
  await page.click('input[type="submit"][value="Add media"]');
  await page.waitForTimeout(200);

  // Click input type checkbox with name media_library_select_form[6]
  await page.click('input[type="checkbox"][name="media_library_select_form[6]"]');
  await page.waitForTimeout(500);

  // Click the first of .media-library-select
  await page.click('button.media-library-select');
  await page.waitForTimeout(500);

  // Inside .ck-editor__editable click the first p and type "This is a challenge"
  await page.click('.ck-editor__editable p');
  await page.keyboard.type('This is a challenge');

  // Click the label with for="edit-field-game-mode-2"
  await page.click('label[for="edit-field-game-mode-2"]');

  // Click the input with ID gin-sticky-edit-submit
  await page.click('#gin-sticky-edit-submit');
  await page.waitForTimeout(1000);

  // On the next page expect to see "Challenge time!" in the format expect(locator).toHaveText()
  await expect(page.locator('.header h1')).toHaveText('Challenge time!');
  
  // Get the ID of the page we just created
  const urlParts = page.url().split('/');
  const nid = urlParts[urlParts.length - 1];

  // Go to https://wyciwyg-drupal-backend.ddev.site/node/add/submission
  await page.goto(baseUrl + '/node/add/submission');

  // In the input with ID edit-title-0-value add the text Submission for "nid"
  await page.fill('#edit-title-0-value', `Submission for ${nid}`);

  // In the input with ID edit-field-name-0-value add the text Submission for "nid"
  await page.fill('#edit-field-name-0-value', `Submission for ${nid}`);

  // Check the checkbox with ID edit-field-personvernerklaering-value
  await page.check('#edit-field-personvernerklaering-value');

  // In the input with ID edit-field-challenge-id-0-value fill the value with "nid"
  await page.fill('#edit-field-challenge-id-0-value', nid);

  // Click the input with ID gin-sticky-edit-submit
  await page.click('#gin-sticky-edit-submit');
  await page.waitForTimeout(500);

  // Check if the first div with class "field__item" has the text "Submission for "nid" 
  await expect(page.locator('.field__item').first()).toHaveText(`Submission for ${nid}`);


  // Go to the challenge page
  await page.goto(baseUrl + `/challenge/${nid}`);

  // Click the link with the text "Delete"
  await page.click('a:has-text("Delete")');
  await page.waitForTimeout(500);

  // Click the input with type submit and the text "Delete"
  await page.click('input[type="submit"][value="Delete"]');
  await page.waitForTimeout(500);

});
