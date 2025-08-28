


import type { Route } from "./+types/_index";
import Navbar from "~/components/navbar";
import { motion } from "framer-motion";
import { redirect } from "react-router";

export function meta({ }: Route.MetaArgs) {
  return [
    { title: "New app" },
    { name: "description", content: "Welcome new app!" },
  ];
}
export async function clientLoader() {
  if (!window.mine)
    throw redirect('/')
}
export default function _index() {
  return <motion.div
    initial={{ opacity: 0, scale: 2 }}
    animate={{ opacity: 1, scale: 1 }}
    className="flex flex-col h-screen mx-auto p-4 gap-4 bg-main w-screen max-w-xl">
    <main className="h-0 shrink grow">
      <div></div>
    </main>
    <Navbar />
  </motion.div>
}
