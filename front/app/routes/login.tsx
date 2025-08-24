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

export function meta({ }: Route.MetaArgs) {
  return [{ title: "ورود" }];
}

const components: IScannerComponents = {
  onOff: true,
  zoom: true,
  finder: true,
};

export default function Login() {
  const devices = useDevices();
  const [device, setDevice] = useState<string>("");
  const [isDone, setIsDone] = useState(false);
  const [code, setCode] = useState("");

  useEffect(() => {
    if (devices.length == 0) return;
    setDevice(devices[0].deviceId);
  }, [devices]);

  const onConfirm = () => {
    setIsDone(true);
    Cookies.set("token", code);
    Cookies.set("device", device);
  };

  const onDeny = () => {
    setCode("");
  };

  const onScan = (res: IDetectedBarcode[]) => {
    if (res.length > 0) {
      setCode(res[0].rawValue);
    }
  };

  if (!devices || devices.length === 0) return <p>{t('qr.notSupported')}</p>;
  if (isDone) return <Navigate to="/register" />;

  return (
    <>
      <Dialog open={code.length > 0} onOpenChange={(open) => !open && setCode("")}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>{t('qr.confirm.title')}</DialogTitle>
            <DialogDescription className="flex flex-col">
              {t('qr.confirm.description')}
              <strong>{code}</strong>
            </DialogDescription>
          </DialogHeader>
          <DialogFooter>
            <Button variant="secondary" onClick={onDeny}>{t('cancel')}</Button>
            <Button onClick={onConfirm}>{t('confirm')}</Button>
          </DialogFooter>
        </DialogContent>
      </Dialog>

      {!code && (
        <div className="h-screen flex flex-col justify-center items-center gap-5 mx-auto w-full max-w-[500px]">
          <div className="w-full h-[512px] rounded-lg overflow-clip shadow">
            <Scanner
              onScan={onScan}
              sound={false}
              components={components}
              scanDelay={100}
              constraints={{ deviceId: device }}
            />
          </div>

          <Select value={device} onValueChange={setDevice}>
            <SelectTrigger className="w-full">
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
        </div>
      )}
    </>
  );
}
