import axios from 'axios'

export default class OpenWeatherMapService {
    // Function that fetches the weather datas of the city requested
    static getWeatherData(city1, city2) {
        // axios.post('')
        // .then(function (response) {
        //     return console.log(response);
        // })
        axios.all([
            axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${city1.value}&units=metric&appid=95542917d76459372397547a96610cd8`),
            axios.post(`https://api.openweathermap.org/data/2.5/weather?q=${city2.value}&units=metric&appid=95542917d76459372397547a96610cd8`),
        ])
        .then(axios.spread((data1, data2) => {
            // output of req.
            console.log('data1', data1.data, 'data2', data2.data)

            return {
                data1: data1.data,
                data2: data2.data
            }

            // return {
            //     c1lat: data1.data.coord.lat,
            //     c1lon: data1.data.coord.lon,

            //     c2lat: data2.data.coord.lat,
            //     c2lon: data2.data.coord.lon
            // }
        }))
        // .then(axios.all([
        //     axios.post(`https://api.openweathermap.org/data/2.5/forecast?lat=${data1.data.coord.lat}&lon=${data1.data.coord.lon}.04&exclude=hourly,daily&units=metric&appid=95542917d76459372397547a96610cd8`),
        //     axios.post(`https://api.openweathermap.org/data/2.5/forecast?lat=${data2.data.coord.lat}&lon=${data2.data.coord.lon}.04&exclude=hourly,daily&units=metric&appid=95542917d76459372397547a96610cd8`),
        // ]))
    }

    // static getWeatherDataFull(c1lat, c1lon, c2lat, c2lon) {
    //     let c1 = [c1lat, c1lon]
    //     let c2 = [c2lat, c2lon]
        
    //     console.log(c1, c2);
    // }
}