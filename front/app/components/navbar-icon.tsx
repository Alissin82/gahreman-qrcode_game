import { motion } from "framer-motion";
import { useLocation, useNavigate } from "react-router"
import { cn } from "~/lib/utils"
interface prop {
  icon: string,
  children: string,
  path?: string
}

export default function NavbarIcon({ icon, children, path }: prop) {
  let nav = useNavigate();
  let active = useLocation().pathname == "/" + (path ?? icon);

  return <div
    onClick={() => nav("/" + (path ?? icon))} className={
      cn("bg-linear-to-b from-white/0 to-white/0  py-3 px-5 flex flex-col items-center justify-center gap-2 rounded-2xl text-white cursor-pointer transition-all",
        active && "from-[#074F9A] to-[#EF4770]")
    }>
    <img src={`/assets/icon/${icon}.svg`} alt="" />
    <div>{children}</div>
  </div>

}

