import {
  isRouteErrorResponse,
  Links,
  Meta,
  Outlet,
  Scripts,
  ScrollRestoration,
} from "react-router";

import type { Route } from "./+types/root";
import { ThemeProvider } from "~/components/ui/theme-provider";
import "./app.css";
import { StateError } from "./lib/error";
import { initI18n } from "~/lib/i18n";
import "~/lib/i18n";

export const links: Route.LinksFunction = () => [
  { rel: "preconnect", href: "https://fonts.googleapis.com" },
  {
    rel: "preconnect",
    href: "https://fonts.gstatic.com",
    crossOrigin: "anonymous",
  },
  {
    rel: "stylesheet",
    href: "https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap",
  },
];
declare global {
  interface Window { teams: any; mine: any }
}

export function Layout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" dir="rtl">
      <head>
        <meta charSet="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <Meta />
        <Links />
      </head>
      <body className="bg-main overflow-hidden">
        <ThemeProvider>{children}</ThemeProvider>
        <ScrollRestoration />
        <Scripts />
      </body>
    </html>
  );
}

enum ColorTypes {
  Girl = 'girl',
  Boy = 'boy'
}
const colors: Record<ColorTypes, string[]> = {
  [ColorTypes.Girl]: ['#EF4770', '#C73A5C'],
  [ColorTypes.Boy]: ['#074F9A', '#07357B']
};

export default function App() {
  initI18n();

  const gender: ColorTypes = localStorage.getItem('gender') ?? ColorTypes.Boy;
  document.documentElement.style.setProperty('--color-main', colors[gender][0]);
  document.documentElement.style.setProperty('--color-deep', colors[gender][1]);
  return <Outlet />;
}

export function ErrorBoundary({ error }: Route.ErrorBoundaryProps) {
  let message = "Oops!";
  let details = "An unexpected error occurred.";
  let stack: string | undefined;

  if (isRouteErrorResponse(error) || error instanceof StateError) {
    message = error.status === 404 ? "404" : "Error";
    details =
      error.status === 404
        ? "The requested page could not be found."
        : error.statusText || details;
  } else if (import.meta.env.DEV && error && error instanceof Error) {
    details = error.message;
    stack = error.stack;
  }

  return (
    <main className="pt-16 p-4 container mx-auto">
      <h1>{message}</h1>
      <p>{details}</p>
      {stack && (
        <pre className="w-full p-4 overflow-x-auto">
          <code>{stack}</code>
        </pre>
      )}
    </main>
  );
}
