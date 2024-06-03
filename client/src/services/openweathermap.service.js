import axios from 'axios'

export default class OpenWeatherMapService {
    static getWeatherData(city1) {
        // axios.all([
        //     axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${city1.value}&units=metric&appid=95542917d76459372397547a96610cd8`),
        //     axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${city2.value}&units=metric&appid=95542917d76459372397547a96610cd8`),
        // ])
        axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${city1.value}&units=metric&appid=95542917d76459372397547a96610cd8`)
        // .then(response => response.json())
        .then(res => {
            // console.log(res.data);
        })
        // .then(axios.spread((data1, data2) => {
        //     // output of req.
        //     // console.log('data1', data1.data, 'data2', data2.data)
        //     return {
        //         data1: data1.data,
        //         data2: data2.data
        //     }
        // }))
        .catch(error => {
            // console.log(error)
            if(error.name == "AxiosError") {
                // console.log("kdkdk");
                error = "SKSKSKSK"
            }
        })
    }
}