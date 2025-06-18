

self.onmessage = ((e) =>{
  if(e.data == "Empezar"){
  const resultado = findAllPrimes(3000)
  self.postMessage(resultado)
  }
})

const isPrime = ((num) => {
  if (num <= 1) {
    return false;
  }
  for (let i = 2; i <= Math.sqrt(num); i++) {
    if (num % i === 0) {
      return false;
    }
  }
  return true;
})


const findAllPrimes = ((limit) => {
  let re = ""
    for(let i = 0; i < limit; i++){
        if(isPrime(i)){
      re += i.toString()+", "

        }
    }
  return re
})
