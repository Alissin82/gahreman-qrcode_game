
import { redirect } from "react-router";
import type { Route } from "./+types/_index";
import Navbar from "~/components/navbar";
import { motion } from "framer-motion";
import api from "~/lib/axios";

export function meta({ }: Route.MetaArgs) {
  return [
    { title: "New app" },
    { name: "description", content: "Welcome new app!" },
  ];
}
export async function clientLoader() {
  if (!window.mine)
    throw redirect('/')

  return await api.actions.index()
}
export default function _index({
  loaderData,
}: Route.ComponentProps) {
  return <motion.div
    initial={{ opacity: 0, scale: 2 }}
    animate={{ opacity: 1, scale: 1 }}
    className="flex flex-col h-screen mx-auto p-4 gap-4 bg-main w-screen max-w-xl">
    <main className="h-0 shrink grow flex flex-col gap-4">
      <div className="text-3xl text-white">لیست عملیات ها</div>

      {loaderData.map((e, i) =>
        <div className="border bg-black/35 p-3 rounded-2xl text-xl text-white">
          <div className="flex">
            <div>{e.name}</div>
          </div>
        </div>)}

    </main>
    <Navbar />
  </motion.div>
}
