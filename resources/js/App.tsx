import React from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import './App.css';

import { Layout } from "./pages/Layout";
import { ErrorPage } from "./pages/ErrorPage";
import { Home } from "./pages/Home";
import { Songs, SongEdit } from "./pages/Songs";
import { Ordinarium, OrdinariumEdit } from "./pages/Ordinarium";
import { Formulas, FormulasEdit } from "./pages/Formulas";
import { MassSet } from "./pages/Set";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="songs" element={<Songs />} />
          <Route path="songs/*" element={<SongEdit />} />
          <Route path="ordinarium" element={<Ordinarium />} />
          <Route path="ordinarium/*" element={<OrdinariumEdit />} />
          <Route path="formulas" element={<Formulas />} />
          <Route path="formulas/*" element={<FormulasEdit />} />
          <Route path="sets/show/*" element={<MassSet />} />
          <Route path="*" element={<ErrorPage code={404} desc="Nie ma takiej strony" />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
