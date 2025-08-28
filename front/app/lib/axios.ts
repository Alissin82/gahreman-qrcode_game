import axios from 'axios'
const baseURL = import.meta.env.DEV
  ? "http://localhost:8000/"
  : "https://iraniom.silitonix.ir/";
const gate = axios.create({
  baseURL: baseURL,
  withCredentials: false,
})

async function get(path: string) {
  const req = await gate.get(path);
  return req.data;
}

async function post(path: string, data: any) {
  const req = await gate.post(path, data);
  return req.data;
}


const api = {
  base: baseURL,
  teams: {
    index: () => get('/api/teams'),
    show: (id: number) => get(`/api/teams/${id}`),
    put: (data: any, id: number) => post(`/api/teams/${id}`, data),
  },
  actions: {
    index: () => get('/api/actions'),
    show: (id: number) => get(`/api/teams/${id}`),
    put: (data: any, id: number) => post(`/api/teams/${id}`, data),
  }
}

export default api;
