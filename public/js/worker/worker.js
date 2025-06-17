

const isPrime = (num) => {
  if (num <= 1) {
    return false;
  }
  for (let i = 2; i <= Math.sqrt(num); i++) {
    if (num % i === 0) {
      return false;
    }
  }
  return true;
}


const findAllPrimes = (limit) => {
    for(let i = 0; i < limit; i++){
        if(isPrime(i)){
            console.log(i)
        }
    }
}

findAllPrimes(3000000)