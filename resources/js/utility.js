export function setDataRegister(params) {
    const dataRegister = JSON.parse(localStorage.getItem('dataRegister'));
    console.log('data asal:',dataRegister);
    if (!dataRegister) {
        localStorage.setItem('dataRegister',JSON.stringify(params));
    }else{
      const mergedData = { ...dataRegister, ...params };
      console.log('Merged Data:',mergedData);
      localStorage.setItem('dataRegister', JSON.stringify(mergedData));
    }
}