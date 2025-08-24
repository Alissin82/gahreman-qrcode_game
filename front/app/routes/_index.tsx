import { redirect } from "react-router";
import type { Route } from "./+types/_index";
import { t } from "i18next";

export function meta({ }: Route.MetaArgs) {
  return [
    { title: "New app" },
    { name: "description", content: "Welcome new app!" },
  ];
}

export async function clientLoader() {
  if (!document.cookie)
    throw redirect("/login");
}

export default function _index() {
  return <div>{t("hello")}</div>;
}
