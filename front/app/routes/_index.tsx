import { redirect } from "react-router";
import type { Route } from "./+types/_index";
import Cookies from "js-cookie";
import api from "~/lib/axios";
import { create } from 'zustand'
export function meta({ }: Route.MetaArgs) {
  return [
    { title: "New app" },
    { name: "description", content: "Welcome new app!" },
  ];
}

export async function clientLoader() {
  const token = Cookies.get("token")
  if (
    !localStorage.getItem('gender') ||
    !localStorage.getItem('camera') ||
    !localStorage.getItem('team') ||
    !token
  ) throw redirect("/login");

  const teams = await api.teams.index();
  const mine = teams.find(o => o.hash === token);
  window.teams = teams
  window.mine = mine
  throw redirect('/home')
}

export default function _index() {
  return <></>;
}
