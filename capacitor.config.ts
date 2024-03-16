import { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.wpww.sz3',
  appName: 'Szpiewnik Szybkiego Szukania',
  webDir: 'public/build',
  server: {
    androidScheme: 'https'
  }
};

export default config;
