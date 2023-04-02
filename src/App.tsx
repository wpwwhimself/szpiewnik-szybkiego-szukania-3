import { BrowserRouter, Routes, Route } from "react-router-dom";
import './App.css';

import { Layout } from "./pages/Layout";
import { ErrorPage } from "./pages/ErrorPage";
import { Home } from "./pages/Home";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Layout title="Pulpit" />}>
          <Route index element={<Home />} />
          {/* <Route path="programmer" element={<Programista />} /> */}
          <Route path="*" element={<ErrorPage code={404} desc="Nie ma takiej strony" />} />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default App;
