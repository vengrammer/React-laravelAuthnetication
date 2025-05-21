import {Route, Routes} from 'react-router-dom'
import Home from './assets/Home';
import Login from './Login';
import Register from './Register';
import Dashboard from './Dashboard';


function Router(){
    return(
        <Routes>
            <Route path='/' element={<Home/>}/>
            <Route path='/login' element={<Login/>}/>
            <Route path='/register' element={<Register/>}/>
            <Route path='/dashboard' element={<Dashboard/>}/>
        </Routes>
    )
}

export default Router;