import type { Route } from "./+types/register"
import { t } from "i18next";
import { Input } from "~/components/ui/input"
import { useState } from "react";
import { Button } from "~/components/ui/button";
import { ScrollArea } from "~/components/ui/scroll-area";
import { Separator } from "@radix-ui/react-select";
import { Slider } from "~/components/ui/slider";
import { Form, useSubmit } from "react-router";
import { DeleteIcon, Trash } from "lucide-react";

const phoneRegex = /^(?:0?9|\+?989)\d{2}\W?\d{3}\W?\d{4}$/;

export function meta({ }: Route.MetaArgs) {
  return [
    { title: "ثبت نام گروه" },
  ];
}

interface User {
  name: string;
  phone: string;
  validName?: boolean;
  validPhone?: boolean;
}
interface Team {
  name: string;
  validName?: boolean;
  color: string;
}
function hslToHex(h: number, s: number, l: number) {
  l /= 100;
  const a = s * Math.min(l, 1 - l) / 100;
  const f = (n: number) => {
    const k = (n + h / 30) % 12;
    const color = l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
    return Math.round(255 * color).toString(16).padStart(2, '0');   // convert to Hex and prefix "0" if needed
  };
  return `#${f(0)}${f(8)}${f(4)}`;
}

export async function clientAction({
  request,
}: Route.ClientActionArgs) {
  return true;
}

export default function register() {
  const [team, setTeam] = useState<Team>({ name: '', color: '#ff0000' });
  const [admin, setAdmin] = useState<User>({ name: '', phone: '' });
  const [userList, setUserList] = useState<User[]>(new Array(5).fill({ name: '', phone: '' }));
  const [hue, setHue] = useState<number>(0);
  const submit = useSubmit();

  const updateTeam = (field: keyof Team, value: string) => {
    const newteam = { ...team, [field]: value };
    newteam.validName = !!newteam.name;
    setTeam({ ...newteam, [field]: value });
  }
  const setColor = (hue: number) => {
    const color = hslToHex(hue, 100, 50); // Full saturation and lightness at 50%
    setHue(hue);
    setTeam({ ...team, color });
  }
  const updateAdmin = (field: keyof User, value: string, set: (i: User) => void) => {
    const newadmin = { ...admin, [field]: value };
    newadmin.validPhone = phoneRegex.test(newadmin.phone);
    newadmin.validName = !!newadmin.name;
    set({ ...newadmin, [field]: value });
  }

  const updateUserArray = (index: number, field: keyof User, value: string) => {
    const updatedList = [...userList];
    updatedList[index] = { ...updatedList[index], [field]: value };
    updatedList[index].validPhone = phoneRegex.test(updatedList[index].phone);
    updatedList[index].validName = !!updatedList[index].name;
    setUserList(updatedList);
  };

  const addUser = () => {
    setUserList([...userList, { name: '', phone: '' }]);
  };

  const validate = (): boolean => {

    let userValidationPassed = true;

    team.validName = !!team.name;
    userValidationPassed = userValidationPassed && team.validName;

    setTeam(team);

    admin.validPhone = phoneRegex.test(admin.phone);
    admin.validName = !!admin.name;
    userValidationPassed = userValidationPassed && admin.validPhone && admin.validName;

    setAdmin(admin);

    const validator = userList.map((u) => {
      u.validPhone = phoneRegex.test(u.phone);
      u.validName = !!u.name;

      userValidationPassed = userValidationPassed && u.validPhone && u.validName;
      return u;
    });

    if (!userValidationPassed) {
      setUserList(validator);
      return false;
    }
    return true;
  };

  const handleSubmit = () => {
    if (!validate()) return;
    submit({});
  };

  return <div className="flex flex-col items-center justify-center p-4 h-screen gap-4">
    <h1 className="text-center text-4xl font-extrabold tracking-tight text-balance">
      {t('register.title')}
    </h1>
    <ScrollArea className="max-w-md grow h-0">
      <div className="flex flex-col gap-2 p-2" >
        <p className="text-sm text-muted-foreground text-right">
          {t('register.description')}
        </p>
        <Separator className="my-2" />
        <h3 className="text-lg font-semibold text-right">
          {t('register.teamTitle')}
        </h3>
        <div className="flex gap-2">
          <Slider
            onValueChange={(e) => setColor(e[0])}
            step={1}
            min={0}
            max={360}
            value={[hue]}
            className="rainbow-slider"
            style={{
              '--filled-percent': `${(hue / 360) * 100}%`,
              '--team-color': team.color,
            } as React.CSSProperties}
          />
          <Input
            onChange={(e) => updateTeam('name', e.target.value)}
            type="text"
            placeholder={t('register.teamNamePlaceholder')}
            className={team.validName === false ? 'border-red-800 text-red-800 placeholder:text-red-800' : ''}
            value={team.name}
          />
        </div>
        <Separator className="my-2" />
        <h3 className="text-lg font-semibold text-right">
          {t('register.adminTitle')}
        </h3>
        <div className="flex gap-2">
          <Input
            onChange={(e) => updateAdmin('phone', e.target.value, setAdmin)}
            type="tel"
            placeholder={t('register.phoneNamePlaceholder')}
            className={admin.validPhone === false ? 'border-red-800 text-red-800 placeholder:text-red-800' : ''}
            value={admin.phone}
          />
          <Input
            onChange={(e) => updateAdmin('name', e.target.value, setAdmin)}
            type="text"
            placeholder={t('register.userNamePlaceholder')}
            className={admin.validName === false ? 'border-red-800 text-red-800 placeholder:text-red-800' : ''}
            value={admin.name}
          />
        </div>
        <Separator className="my-2" />
        <h3 className="text-lg font-semibold text-right">
          {t('register.userTitle')}
        </h3>
        {userList.map((user, index) => <div key={index} className="flex gap-2">
          <Input
            onChange={(e) => updateUserArray(index, 'phone', e.target.value)}
            type="tel"
            placeholder={t('register.phoneNamePlaceholder')}
            value={user.phone}
            className={user.validPhone === false ? 'border-red-800 text-red-800 placeholder:text-red-800' : ''}
          />
          <Input
            onChange={(e) => updateUserArray(index, 'name', e.target.value)}
            type="text"
            placeholder={t('register.userNamePlaceholder')}
            className={user.validName === false ? 'border-red-800 text-red-800 placeholder:text-red-800' : ''}
            value={user.name}
          />
          <Button
            type="button"
            variant="outline"
            onClick={() => {
              const updatedList = [...userList];
              updatedList.splice(index, 1);
              setUserList(updatedList);
            }}
            className="self-start cursor-pointer"
          >
            <Trash />
          </Button>
        </div>)}
      </div>
    </ScrollArea>
    <div className="flex gap-2 mt-4">
      <Button onClick={addUser}>
        {t('register.addUserButton')}
      </Button>
      <Button type="submit" onClick={handleSubmit} className="cursor-pointer">
        {t('button.submit')}
      </Button>
    </div>
  </div>

}
