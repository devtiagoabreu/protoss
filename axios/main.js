const urlLoginUser = "http://localhost:8000/api/auth/login"

function loginUser() {
  event.preventDefault()
  axios.post(urlLoginUser, {
    email: document.getElementById('email').value,
    password: document.getElementById('password').value
  })
  .then(response => {
    const data = response.data

    if (data.token) {
      console.log(JSON.stringify(data.token))
    }
  })
  .catch(error => console.log(error))
}

