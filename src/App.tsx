import { BrowserRouter, Routes, Route } from "react-router-dom";
import './App.css';

import { Layout } from "./pages/Layout";
import { ErrorPage } from "./pages/ErrorPage";
import { Home } from "./pages/Home";
import { Songs } from "./pages/Songs";
import { SongEdit } from "./pages/SongEdit";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="songs" element={<Songs />} />
          <Route path="songs/*" element={<SongEdit />} />
          <Route path="*" element={<ErrorPage code={404} desc="Nie ma takiej strony" />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
