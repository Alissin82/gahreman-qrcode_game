import { Navigate, redirect } from "react-router";
import { motion } from 'framer-motion'
import type { Route } from "./+types/_index";
import { Button } from "~/components/ui/button";
import { useState } from "react";
export function meta({ }: Route.MetaArgs) {
  return [
    { title: "New app" },
    { name: "description", content: "Welcome new app!" },
  ];
}

export default function _index() {

  const [isDone, setIsDone] = useState(false);
  if (isDone) return <Navigate to="/" />;
  return <motion.div
    initial={{ opacity: 0, scale: 2 }}
    animate={{ opacity: 1, scale: 1 }}
    className="bg-[url('/assets/img/bg.jpg')] bg-cover fade-in">
    <div className="h-screen gap-10 flex flex-col justify-center items-center bg-black/45">
      <img className="w-[268px]" src="assets/img/logo.png" />
      <h1 className="text-4xl max-w-sm text-white">خوش آمدید به دنیای ماموریت های جذاب</h1>
      <Button className="text-4xl p-10 rounded-3xl cursor-pointer animate-bounce" onClick={() => setIsDone(true)}>شروع بازی</Button>
    </div>
  </motion.div>;
}
