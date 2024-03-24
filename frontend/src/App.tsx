import React from 'react';
import "./app.css"
import { Header } from './components/Header';
import { Footer } from './components/Footer';

function App() {
  return (
    <div className="App">
      <Header title={"nothing"} />
      <div id="main-wrapper"></div>
      <Footer />
    </div>
  );
}

export default App;
