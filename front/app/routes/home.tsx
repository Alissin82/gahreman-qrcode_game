

import type { Route } from "./+types/_index";
import Navbar from "~/components/navbar";
import { motion } from "framer-motion";
import TopBar from "~/components/home-topbar";
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
    <TopBar />
    <div className="flex gap-4">
      <div className="flex items-center gap-4 p-4 grow rounded-md bg-deep text-[#E4A1FF]">
        <img src="/assets/icon/cup.svg" alt="" />
        {window.mine.score}
      </div>
      <div className="flex items-center gap-4 p-4 grow rounded-md bg-deep text-[#FCB917]">
        <img src="/assets/icon/coin.svg" alt="" />
        {window.mine.coin}
      </div>
    </div>
    <div></div>
    <main className="h-0 shrink grow">
      <div></div>
    </main>
    <div></div>
    <Navbar />
  </motion.div>
}
