const urlPing = "http://localhost:8000/api/ping"
const urlAddNewUser = "http://localhost:8000/api/user"


function ping() {
  axios.get(urlPing)
  .then(response => {
    const data = response.data
    renderResults.textContent = JSON.stringify(data)
  })
  .catch(error => console.log(error))
}

function addNewUser(newUser) {
  axios.post(urlAddNewUser, newUser)
  .then(response => {
    const data = response.data
    renderResults.textContent = JSON.stringify(data)
  })
  .catch(error => console.log(error))
}

ping()

addNewUser()