import axios from 'axios'

export default class OpenWeatherMapService {
    // Function that fetches the weather datas of the city requested
    static getWeatherData(city1, city2) {
        axios.post('http://127.0.0.1:8000/api')
        .then(function (response) {
            return console.log(response);
        })
    }
}