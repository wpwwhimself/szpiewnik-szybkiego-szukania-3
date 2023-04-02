import { Outlet } from "react-router-dom";
import { Header, Footer } from '../components/HnF';

declare interface LayoutProps{
  title: string,
};

export function Layout({title}: LayoutProps){
  document.title = `${title} | Szpiewnik Szybkiego Szukania`;

  return (
    <div className="App">
      <Header title={title} />
      <div id="main-wrapper">
        <Outlet />
      </div>
      <Footer />
    </div>
  );
}