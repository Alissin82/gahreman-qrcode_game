import NavbarIcon from "./navbar-icon"

const icons: { title: string, icon: string, path?: string }[] = [
  { title: "خانه", icon: "home", path: "/" },
  { title: "ماموریت‌ها", icon: "timer" },
  { title: "جوایز دریافتی", icon: "medal" },
  { title: "تیم‌ها", icon: "team" },
  { title: "پازل", icon: "puzzle" },
]

interface prop {
}
export default function Navbar({ }: prop) {

  return <div className="flex flex-row justify-between w-full bg-deep p-2 rounded-3xl shadow-lg  mx-auto">
    {icons.map((e, i) => <NavbarIcon icon={e.icon} >{e.title}</NavbarIcon>)}
  </div>

}
