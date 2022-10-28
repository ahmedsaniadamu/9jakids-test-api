# 9jakids Test Api

## Introduction
  9jakids test APi is Rest Api Build with Laravel ( PHP framework for web artisans ) 
<br>
# Running The Project 
&nbsp; To sucessfully run the project install a composer software or click here to download www.getcomposer.org
 <br> The next step is to open your terminal and run composer install or composer update to install all the necessary dependency.

 <br>
## Api Usage
  To successfully consume the api here is some basic example of the Api usage you can use Api testing softwares like postman to the endpoints.

&nbsp; # Api Enpoints
   Assuming the project is hosted on locahost:800 in a local development server
   <br>

     <h4> &nbsp;&nbsp;  1.  http:localhost:800/api/check-mail  &nbsp;&nbsp; Method = POST  </h4>
     <br>
      This endpoint verifies if a user can proceed to the next stae of the registration.
     
     <br>
       If a request is sent, the server will first authenticate the request to check and see if the email or phone number is already taken. If so then the server will automatically generate a login link and sent to parent email address and return a json response in this format.
      <br />

      <pre>
          {
             success : false,
             message : 'The email has already been taken. A login link is send to your email address'                 
          }
     </pre>
     
     <br><br>


 <h4> &nbsp;&nbsp;  2.  http:localhost:800/api/register  &nbsp;&nbsp; Method = POST  </h4>
 <p>
     This endpoint registers parent with specified number of children at once so it espect an nested object as the body of the request e.g
     <pre>
          {
             name : 'uche',
             email : 'uche@gmail.com',
             phone: '080302848757',
             children : [
                    {
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male'
                    },
                    {
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male'
                    },
                    {
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male'
                    }
                    //.....//
             ]             
          }
     </pre>
           
     if the email is not taken and the request is authenticated the server will renerate a unique 6 digit code for each child then the Api will return a json format like this:
     <pre> 
        {
             success : true,
             status : 201,
             name : 'uche',
             email : 'uche@gmail.com',
             token : 
             message : 'registration is successful' 
        }
     </pre>
 </p>
<br>

<h4> &nbsp;&nbsp;  3.  http:localhost:800/api/login  &nbsp;&nbsp; Method = POST  </h4>
 <p>
    This endpoint i used to authenticate parents and returns a json response.
    it espect credentials to be sent by the client and authorization header with the beare token e.g
    <pre>
          {              
             email : 'uche@gmail.com', 
             password : '**********'  ,                                    
          }
    </pre>
     After the request is authenticated a json response will be sent back to client along with all 
     childrens data e.g
    <pre>
          {
             id : 1
             name : 'uche',
             email : 'uche@gmail.com',
             phone: '080302848757',
             children : [
                    {
                        id : 1,
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male',
                        code : 654677
                    },
                    {
                        id : 2,
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male',
                        code: 869584
                    },
                    {
                        id : 3
                        name : 'jhon doe',
                        age_range :  '5-10',
                        gender : 'male',
                        code : 895765
                    }
                    //.....//
             ]             
          }
     </pre>

 <h4> &nbsp;&nbsp;  4.  http:localhost:800/api/login  &nbsp;&nbsp; Method = POST  </h4>
 <p>
    This endpoint i used to authenticate children and returns a json response.
    it espect date to be sent by the client i.e the 6 digit code 
    <pre>
          {              
             code :  123456,                                                   
          }
    </pre>
     After the request is authenticated a json response will be sent back to client along with all 
     childrens data e.g

        {
            status : 200,
            id : 2,
            name : 'jhon doe',
            age_range :  '5-10',
            gender : 'male',
            code: 869584
        },
    <pre>
 </p>
        
# Protected Route
This route allows only authenticated users to make request the routes are protected using laravel sanctum which is a lighweight authentication system for single page applications and mobile apps.
the only route that was protectected by this api is the logout endpoint which expect the bearer
token that was sent by during login.

<h3> EndPoint </h3>
<h4> &nbsp;&nbsp;  4.  http:localhost:800/api/logout  &nbsp;&nbsp; Method = POST  </h4>
<br>This route expect only a bearer token from the authorization header. It checks the token and delete the personal access token. A Json response is returned in the folloing format
    <pre> 
        {
             success : true,
             status : 200,
             name : 'uche',               
             message : 'logged out is successfully!' 
        }
     </pre>


