import type { Route } from "./+types/login";

import Cookies from "js-cookie"
import { t } from "i18next";
import { Scanner, useDevices, type IDetectedBarcode, type IScannerComponents } from '@yudiel/react-qr-scanner';
import { useEffect, useState } from "react";
import { Navigate } from "react-router";
import { Button } from "~/components/ui/button";
import { DialogFooter, DialogHeader } from "~/components/ui/dialog";
import { Dialog, DialogContent, DialogDescription, DialogTitle } from "~/components/ui/dialog";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "~/components/ui/select";
import { motion } from "framer-motion"
export function meta({ }: Route.MetaArgs) {
  return [{ title: "ورود" }];
}

const components: IScannerComponents = {
  onOff: true,
  torch: true,
  zoom: true,
  finder: true,
};

enum ColorTypes {
  Girl = 'girl',
  Boy = 'boy'
}

const colors: Record<ColorTypes, string[]> = {
  [ColorTypes.Girl]: ['#EF4770', '#C73A5C'],
  [ColorTypes.Boy]: ['#074F9A', '#07357B']
};

export default function Login() {
  const devices = useDevices();
  const [device, setDevice] = useState<string>("");
  const [gender, setGenderState] = useState<string>("");
  const [isDone, setIsDone] = useState(false);
  const [code, setCode] = useState("");
  const [title, setTitle] = useState("");

  useEffect(() => {
    if (devices.length == 0) return;
    setDevice(devices[0].deviceId);
    setGender(ColorTypes.Boy);
  }, [devices]);

  const onConfirm = () => {
    setIsDone(true);
    localStorage.setItem('camera', device);
    localStorage.setItem('gender', gender);
    localStorage.setItem('team', title);
    Cookies.set("token", code);
  };

  const setGender = (type: ColorTypes) => {
    document.documentElement.style.setProperty('--color-main', colors[type][0]);
    document.documentElement.style.setProperty('--color-deep', colors[type][1]);
    setGenderState(type);
  };

  const onDeny = () => {
    setCode("");
  };

  const onScan = (res: IDetectedBarcode[]) => {
    if (res.length < 0) return false;
    const { hash, title }: { title: string, hash: string } = JSON.parse(res[0].rawValue);

    setCode(hash);
    setTitle(title);
  };

  if (!devices || devices.length === 0) return <p>{t('qr.notSupported')}</p>;
  if (isDone) return <Navigate to="/welcome" />;

  return (
    <>
      <Dialog open={code.length > 0} onOpenChange={(open) => !open && setCode("")}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>{t('qr.confirm.title')}</DialogTitle>
            <DialogDescription className="flex flex-col">
              {t('qr.confirm.description')}
              <strong>نام تیم: {title}</strong>
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="secondary" onClick={onDeny}>{t('cancel')}</Button>
            <Button onClick={onConfirm}>{t('confirm')}</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {!code && (
        <motion.div
          initial={{ opacity: 0, scale: 2 }}
          animate={{ opacity: 1, scale: 1 }}
          className="h-screen flex flex-col justify-center items-center gap-5 mx-auto w-full max-w-[500px] p-2">
          <div className="bg-accent w-full h-[512px] rounded-lg overflow-clip shadow">
            <Scanner
              onScan={onScan}
              sound={true}
              formats={[
                'qr_code',
                'micro_qr_code',
                'rm_qr_code',
                'maxi_code',
                'pdf417',
                'aztec',
                'data_matrix',
                'matrix_codes',
                'dx_film_edge',
                'databar',
                'databar_expanded',
                'codabar',
                'code_39',
                'code_93',
                'code_128',
                'ean_8',
                'ean_13',
                'itf',
                'linear_codes',
                'upc_a',
                'upc_e'
              ]}
              components={components}
              scanDelay={2500}
              constraints={{ deviceId: device }}
            />
          </div>

          <Select value={gender} onValueChange={setGender}>
            <SelectTrigger className="w-full bg-accent">
              <SelectValue placeholder="Select a device" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={'boy'}> پسر </SelectItem>
              <SelectItem value={'girl'}> دختر </SelectItem>
            </SelectContent>
          </Select>

          <Select value={device} onValueChange={setDevice}>
            <SelectTrigger className="w-full bg-accent">
              <SelectValue placeholder="Select a device" />
            </SelectTrigger>
            <SelectContent>
              {devices.map((d, index) => (
                <SelectItem key={d.deviceId || index} value={d.deviceId}>
                  {d.label || `Device ${index + 1}`}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </motion.div>
      )}
    </>
  );
}
