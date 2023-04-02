import { Outlet } from "react-router-dom";
import { Header, Footer } from '../components/HnF';

export function Layout(){
  return (
    <div className="App">
      <Header />
      <div id="main-wrapper">
        <Outlet />
      </div>
      <Footer />
    </div>
  );
}