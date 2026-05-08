
console.log("Hola mundo desde list.script.js")

console.log(APP_ADMIN_URL + "/users")

// GET
fetch(APP_ADMIN_URL + "/users/endpoint/list", {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json"
  }
})
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log(data);
    } else {
      console.error(data);
    }
  })
  .catch(error => console.error("Error:", error));

// // POST
// fetch(APP_ADMIN_URL + "/users/endpoint/save", {
//   method: "POST",
//   headers: {
//     "Content-Type": "application/json",
//     "Accept": "application/json"
//   },
//   body: JSON.stringify({
//     name: "Nuevo Usuario",
//     email: "nuevo@correo.com"
//   })
// })
//   .then(response => response.json())
//   .then(data => {
//     if (data.success) {
//       console.log("Éxito:", data.message);
//     }
//   })
//   .catch(error => console.error("Error:", error));

// // PUT
// fetch(APP_ADMIN_URL + "/users/endpoint/update", {
//   method: "PUT",
//   headers: {
//     "Content-Type": "application/json",
//     "Accept": "application/json"
//   },
//   body: JSON.stringify({
//     name: "Nuevo Usuario",
//     email: "nuevo@correo.com"
//   })
// })
//   .then(response => response.json())
//   .then(data => {
//     if (data.success) {
//       console.log("Éxito:", data.message);
//     }
//   })
//   .catch(error => console.error("Error:", error));

// // DELETE
// fetch(APP_ADMIN_URL + "/users/endpoint/delete", {
//   method: "DELETE",
//   headers: {
//     "Content-Type": "application/json",
//     "Accept": "application/json"
//   },
//   body: JSON.stringify({
//     name: "Nuevo Usuario",
//     email: "nuevo@correo.com"
//   })
// })
//   .then(response => response.json())
//   .then(data => {
//     if (data.success) {
//       console.log("Éxito:", data.message);
//     }
//   })
//   .catch(error => console.error("Error:", error));
