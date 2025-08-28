import { User } from "lucide-react"
import Cookie from 'js-cookie'
import {
  Avatar,
  AvatarFallback,
  AvatarImage,
} from "~/components/ui/avatar"
import api from "~/lib/axios";
import { Button } from "./ui/button";

export default function TeamTopBar() {

  return <div className="bg-deep p-4 rounded-2xl flex items-center gap-3">
    <Avatar>
      <AvatarImage
        src={`${api.base}storage/${window.mine.content}`}
        alt="@evilrabbit"
      />
      <AvatarFallback>
        <User />
      </AvatarFallback>
    </Avatar>
    <div className="grow">
      <div className="text-white text-2xl">{window.mine.name}</div>
      <div className="flex gap-2">
        <div className="text-white">{window.mine.bio}</div>
        <Button className="p-1 h-6">مشاهده اعضا</Button>
      </div>
    </div>
    <div className="p-1 rounded border border-[#FCBD1E] w-8 aspect-square flex justify-center items-center">
      <img src="/assets/icon/notify.svg" alt="" />
    </div>
    <div className="p-1 rounded border border-[#88DFFF] w-8 aspect-square flex justify-center items-center">
      <img src="/assets/icon/settings.svg" alt="" />
    </div>
  </div>
}

