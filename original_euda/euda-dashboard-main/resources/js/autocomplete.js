
// function autocompleteAddress() {
//     console.log("=====================================");
//     const input = document.getElementById('search-address-input');
//     console.log(input);
//     console.log('==========================');

//     document.getElementById('place-id-input').value = '';
//     document.getElementById('address-input').value = '';
//     document.getElementById('city-input').value = '';
//     document.getElementById('state-input').value = '';
//     document.getElementById('zipcode-input').value = '';

//     const event = new Event('keydown');
//     event.keyCode = 13; // Enter key code
//     input.dispatchEvent(event);
// }

// function initializeAutocomplete() {
//     const input = document.getElementById('search-address-input');
//     const autocomplete = new google.maps.places.Autocomplete(input);

//     autocomplete.addListener('place_changed', function() {
//         const place = autocomplete.getPlace();

//         document.getElementById('place-id-input').value = place.place_id;
//         document.getElementById('address-input').value = place.formatted_address;

//         place.address_components.forEach(function(component) {
//             if (component.types.includes('locality')) {
//                 document.getElementById('city-input').value = component.long_name;
//             } else if (component.types.includes('administrative_area_level_1')) {
//                 document.getElementById('state-input').value = component.long_name;
//             } else if (component.types.includes('postal_code')) {
//                 document.getElementById('zipcode-input').value = component.long_name;
//             }
//         });
//     });
// }

// document.addEventListener('DOMContentLoaded', function() {
//     initializeAutocomplete();
// });
