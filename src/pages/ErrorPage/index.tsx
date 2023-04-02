declare interface ErrorPageProps{
  code: number,
  desc: string,
}

export function ErrorPage({code, desc}: ErrorPageProps){
  return(
    <div className="flex-down center">
      <h1 className="error-code">{code}</h1>
      <p>{desc}</p>
    </div>
  )
}