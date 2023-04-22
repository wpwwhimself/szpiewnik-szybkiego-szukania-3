import React from 'react';
import ReactDOM from 'react-dom';
import reportWebVitals from './reportWebVitals';
import { MassSet } from './pages/Set';

ReactDOM.render(
  <React.StrictMode>
    <MassSet />
  </React.StrictMode>,
document.getElementById('root') as HTMLElement
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
