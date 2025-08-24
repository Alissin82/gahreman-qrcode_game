import axios from 'axios'

const gate = axios.create({
  baseURL: import.meta.env.SERVER_URI + "/api/",
  withCredentials: true,
})

async function post(path: string, data: any) {
  const req = await gate.post(path, data);
  return req.data;
}

const api = {
  login(data: {
    email: string,
    password: string
  }) { return post("login", data) },

  register(data: {
    name: string,
    email: string,
    password: string,
    gender: boolean,
    age: number,
  }) { return post("register", data) },
}

export default api;
