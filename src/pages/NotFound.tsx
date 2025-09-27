import { useLocation, Link } from "react-router-dom";
import { useEffect } from "react";

const NotFound = () => {
  const location = useLocation();

  useEffect(() => {
    console.error("404 Error: User attempted to access non-existent route:", location.pathname);
  }, [location.pathname]);

  return (
    <div className="flex min-h-screen items-center justify-center bg-background">
      <div className="text-center max-w-md mx-auto px-4">
        <div className="w-24 h-24 bg-gradient-youtube rounded-full flex items-center justify-center mx-auto mb-8">
          <span className="text-white font-bold text-3xl">404</span>
        </div>
        <h1 className="mb-4 text-4xl font-bold text-foreground">Page Not Found</h1>
        <p className="mb-8 text-xl text-muted-foreground">
          Oops! The page you're looking for doesn't exist or has been moved.
        </p>
        <Link 
          to="/" 
          className="inline-flex items-center px-6 py-3 bg-gradient-youtube text-white font-medium rounded-lg hover:shadow-glow transition-all duration-300"
        >
          Return to Home
        </Link>
      </div>
    </div>
  );
};

export default NotFound;
