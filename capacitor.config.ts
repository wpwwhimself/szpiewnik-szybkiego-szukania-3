import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.wpww.sz3',
  appName: 'Szpiewnik Szybkiego Szukania',
  webDir: 'frontend/public',
  server: {
    androidScheme: 'https'
  }
};

export default config;
