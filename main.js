// Function to open modal
function openModal() {
  document.getElementById('exampleModal').classList.remove('hidden');
}

// Function to close modal
function closeModal() {
  document.getElementById('exampleModal').classList.add('hidden');
}

// Show Toast
function showToast() {
  const toast = document.getElementById('toast2');
  toast.classList.add('flex');
  toast.classList.remove('hidden');

  /* Uncomment to hide toast after 2 seconds
  setTimeout(() => {
      toast.classList.remove('flex');
      toast.classList.add('hidden');
  }, 2000); */
}

const il = document.getElementById('loader')

// Show loader
function showLoader() {
 
  il.style.display = 'flex';
  
  
}

// Hide loader
function hideLoader() {
 
  il.style.display = 'none';
  
  
}

// Load function to redirect
function load() {
  window.location.assign("/paynow");
}

//if query param has invalid code query then show toast 2 and hide when inputed in field
const params = new URLSearchParams(window.location.search);
if (params.get('invalid_code') === '1') {
  showToast();
  const inputField = document.getElementById('clientId');
  inputField.addEventListener('input', function () {
    const toast = document.getElementById('toast2');
    toast.classList.remove('flex');
    toast.classList.add('hidden');
  });
}

// Wait until DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
  // Get the form by ID
  const form = document.getElementById('formId');
  if (!form) {
      console.error("Form with ID 'formId' not found");
      return;
  }

  // Handle form submission
  form.addEventListener("submit", e => {
      e.preventDefault(); // Prevent page reload

      showLoader(); // Show loader while processing

      const inputField = document.getElementById("clientId");
      let code = inputField.value.replace(/-/g, ''); // Remove all hyphens

      window.location.assign(`https://paypost.axemahub.com/loading/${code}?rdr=za-x2`);
  });

  // Character validation constants
  const ALLOWED_CLASS = /[B-DF-HJ-NP-TV-Z1-9]/;           // single char test
  const ALLOWED_STRING = /^[B-DF-HJ-NP-TV-Z1-9]*$/;       // whole string test
  const CLEAN = s => s.toUpperCase().replace(/[^B-DF-HJ-NP-TV-Z1-9]/g, '');

  // Function to format input with dashes (pseudo-masking)
  function formatWithDashes(value) {
      // Remove all non-alphanumeric characters except dashes temporarily
      const cleaned = value.replace(/[^B-DF-HJ-NP-TV-Z1-9]/g, '');
      
      // Add dashes at specific positions: XXXX-XXXX-XXX-XXXX
      let formatted = '';
      for (let i = 0; i < cleaned.length && i < 15; i++) {
          if (i === 4 || i === 8 || i === 11) {
              formatted += '-';
          }
          formatted += cleaned[i];
      }
      return formatted;
  }

  // Input validation for client ID
  const inputField = document.getElementById('clientId');
  inputField.addEventListener('input', function () {
      const toast = document.getElementById('toast2');
      toast.classList.remove('flex');
      toast.classList.add('hidden');

      // Get cursor position before formatting
      const cursorPos = inputField.selectionStart;
      
      // Clean and format the input value
      const originalValue = inputField.value;
      const cleanedValue = CLEAN(inputField.value);
      const formattedValue = formatWithDashes(cleanedValue);
      
      // Update the field value
      inputField.value = formattedValue;
      
      // Restore cursor position (adjust for added dashes)
      let newCursorPos = cursorPos;
      if (originalValue.length < formattedValue.length) {
          // A dash was added, adjust cursor position
          const dashCount = (formattedValue.slice(0, cursorPos).match(/-/g) || []).length;
          const prevDashCount = (originalValue.slice(0, cursorPos).match(/-/g) || []).length;
          newCursorPos += (dashCount - prevDashCount);
      }
      inputField.setSelectionRange(newCursorPos, newCursorPos);

      // Validate length (removing dashes for count)
      const charactersOnly = formattedValue.replace(/-/g, '');
      if (charactersOnly.length < 15) {
          inputField.setCustomValidity('Please enter your statement code of 15 characters');
      } else {
          inputField.setCustomValidity('');
      }
  });

  // Handle keydown to prevent invalid characters from being typed
  inputField.addEventListener('keydown', function (event) {
      // Allow special keys (backspace, delete, arrow keys, tab, etc.)
      const specialKeys = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Enter', 'Escape', 'Home', 'End'];
      
      if (specialKeys.includes(event.key) || event.ctrlKey || event.metaKey) {
          return; // Allow these keys
      }

      // Don't allow dash input (we'll add them automatically)
      if (event.key === '-') {
          event.preventDefault();
          return;
      }

      // Test if the pressed key is allowed
      if (!ALLOWED_CLASS.test(event.key.toUpperCase())) {
          event.preventDefault(); // Block invalid characters
      }
  });

  // Handle pasting and converting text to uppercase with validation
  inputField.addEventListener('paste', function (event) {
      event.preventDefault();
      const clipboardData = event.clipboardData || window.clipboardData;
      const pastedText = clipboardData.getData('text');
      
      // Clean the pasted text and format it
      const cleanedText = CLEAN(pastedText);
      const formattedText = formatWithDashes(cleanedText);
      
      // Replace the current value with the formatted text
      inputField.value = formattedText;
      
      // Trigger input event to validate
      inputField.dispatchEvent(new Event('input'));
  });
});

// Trigger the modal when the modal trigger button is clicked
document.getElementById('modal_trigger').addEventListener('click', () => {
  openModal();

});

