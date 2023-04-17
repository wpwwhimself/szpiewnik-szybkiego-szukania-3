import { BrowserRouter, Routes, Route, useNavigate } from "react-router-dom";

import { Layout } from "./pages/Layout";
import { ErrorPage } from "./pages/ErrorPage";
import { Home } from "./pages/Home";
import { Songs, SongEdit } from "./pages/Songs";
import { Ordinarium, OrdinariumEdit } from "./pages/Ordinarium";
import { Formulas, FormulasEdit } from "./pages/Formulas";
import { MassSet } from "./pages/Set";
import { createContext, useState } from "react";
import { Auth } from "./pages/Auth";
import { AuthProps, CheckAuthProps } from "./types";

export const AuthContext = createContext({} as AuthProps);

function App() {
  const [auth, setAuth] = useState(false);
  const checkAuth: CheckAuthProps = (pass) => {
    if(pass === process.env.MIX_AUTH_PASSWORD){
      setAuth(true);
      return true;
    }else{
      alert("Hasło niepoprawne");
      return false;
    }
  }

  return (
    <AuthContext.Provider value={{ auth, checkAuth } as AuthProps}>
    <BrowserRouter>
      <Routes>
        <Route path='/' element={<Layout />}>
          <Route index element={<Home />} />
          <Route path="auth" element={<Auth />} />
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
    </AuthContext.Provider>
  );
}

export default App;
