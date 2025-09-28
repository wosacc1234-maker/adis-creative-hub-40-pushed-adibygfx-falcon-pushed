import { useState, useEffect } from "react"
import { Palette, Play, Zap, CheckCircle, ChevronLeft, ChevronRight, Plus } from "lucide-react"
import { Button } from "@/components/ui/button"
import { Card } from "@/components/ui/card"

interface ServiceFeature {
  text: string
  included: boolean
}

interface Service {
  id: number
  icon: string
  title: string
  description: string
  features: ServiceFeature[]
  price: string
  popular: boolean
  sort_order: number
  is_active: boolean
}

// Default services data (will be replaced by backend data)
const defaultServices: Service[] = [
  {
    id: 1,
    icon: "Palette",
    title: "Logo Design",
    description: "Professional logos that make your brand unforgettable",
    features: [
      { text: "3 Concepts", included: true },
      { text: "Unlimited Revisions", included: true },
      { text: "All File Formats", included: true },
      { text: "Copyright Transfer", included: true }
    ],
    price: "From $149",
    popular: false,
    sort_order: 1,
    is_active: true
  },
  {
    id: 2,
    icon: "Play",
    title: "YouTube Thumbnails",
    description: "Eye-catching thumbnails that boost your click-through rates",
    features: [
      { text: "High CTR Design", included: true },
      { text: "A/B Test Ready", included: true },
      { text: "Mobile Optimized", included: true },
      { text: "24h Delivery", included: true }
    ],
    price: "From $49",
    popular: true,
    sort_order: 2,
    is_active: true
  },
  {
    id: 3,
    icon: "Zap",
    title: "Video Editing",
    description: "Professional video editing that keeps viewers engaged",
    features: [
      { text: "Color Grading", included: true },
      { text: "Motion Graphics", included: true },
      { text: "Sound Design", included: true },
      { text: "Fast Turnaround", included: true }
    ],
    price: "From $299",
    popular: false,
    sort_order: 3,
    is_active: true
  }
]

const iconMap = {
  Palette,
  Play,
  Zap,
  Plus
}

export function ServicesOverview() {
  const [services, setServices] = useState<Service[]>(defaultServices)
  const [currentSlide, setCurrentSlide] = useState(0)
  const [isLoading, setIsLoading] = useState(false)

  // Load services from backend
  useEffect(() => {
    loadServices()
  }, [])

  const loadServices = async () => {
    setIsLoading(true)
    try {
      // In production, this would fetch from your backend API
      // const response = await fetch('/backend/api/services')
      // const data = await response.json()
      // setServices(data.services)
      
      // For now, using default data
      setServices(defaultServices)
    } catch (error) {
      console.error('Error loading services:', error)
    } finally {
      setIsLoading(false)
    }
  }

  const nextSlide = () => {
    setCurrentSlide((prev) => (prev + 1) % services.length)
  }

  const prevSlide = () => {
    setCurrentSlide((prev) => (prev - 1 + services.length) % services.length)
  }

  const goToSlide = (index: number) => {
    setCurrentSlide(index)
  }

  if (isLoading) {
    return (
      <section className="py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center">
            <div className="animate-pulse">Loading services...</div>
          </div>
        </div>
      </section>
    )
  }

  return (
    <section className="py-20">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section header */}
        <div className="text-center mb-16">
          <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
            Services That <span className="text-gradient-youtube">Drive Results</span>
          </h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Professional design services tailored to grow your business and increase conversions.
          </p>
        </div>

        {/* Services Slider */}
        <div className="relative">
          {/* Desktop: Show all services */}
          <div className="hidden md:grid md:grid-cols-3 gap-8">
            {services.filter(service => service.is_active).map((service) => (
              <ServiceCard key={service.id} service={service} />
            ))}
          </div>

          {/* Mobile: Slider */}
          <div className="md:hidden">
            <div className="relative overflow-hidden">
              <div 
                className="flex transition-transform duration-300 ease-in-out"
                style={{ transform: `translateX(-${currentSlide * 100}%)` }}
              >
                {services.filter(service => service.is_active).map((service) => (
                  <div key={service.id} className="w-full flex-shrink-0 px-4">
                    <ServiceCard service={service} />
                  </div>
                ))}
              </div>
            </div>

            {/* Slider controls */}
            <div className="flex items-center justify-center mt-8 space-x-4">
              <Button
                variant="outline"
                size="sm"
                onClick={prevSlide}
                className="w-10 h-10 p-0 rounded-full"
              >
                <ChevronLeft className="h-4 w-4" />
              </Button>

              {/* Dots indicator */}
              <div className="flex space-x-2">
                {services.filter(service => service.is_active).map((_, index) => (
                  <button
                    key={index}
                    onClick={() => goToSlide(index)}
                    className={`w-3 h-3 rounded-full transition-colors ${
                      index === currentSlide ? 'bg-youtube-red' : 'bg-muted'
                    }`}
                  />
                ))}
              </div>

              <Button
                variant="outline"
                size="sm"
                onClick={nextSlide}
                className="w-10 h-10 p-0 rounded-full"
              >
                <ChevronRight className="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>

        {/* Bottom CTA */}
        <div className="text-center mt-16 p-8 bg-gradient-subtle rounded-2xl">
          <h3 className="text-2xl font-bold text-foreground mb-4">
            Need a Custom Package?
          </h3>
          <p className="text-muted-foreground mb-6">
            Let's discuss your project and create a tailored solution that fits your needs and budget.
          </p>
          <Button 
            size="lg"
            className="bg-gradient-youtube hover:shadow-glow transition-all duration-300 font-semibold px-8 py-4"
          >
            Schedule Free Consultation
          </Button>
        </div>
      </div>
    </section>
  )
}

function ServiceCard({ service }: { service: Service }) {
  const IconComponent = iconMap[service.icon as keyof typeof iconMap] || Palette

  return (
    <div 
      className={`relative card-premium ${service.popular ? 'ring-2 ring-youtube-red' : ''}`}
    >
      {service.popular && (
        <div className="absolute -top-3 left-1/2 transform -translate-x-1/2">
          <span className="bg-gradient-youtube text-white px-4 py-1 rounded-full text-sm font-medium">
            Most Popular
          </span>
        </div>
      )}

      <div className="text-center">
        <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-youtube rounded-xl mb-6">
          <IconComponent className="h-8 w-8 text-white" />
        </div>
        
        <h3 className="text-xl font-semibold text-foreground mb-3">{service.title}</h3>
        <p className="text-muted-foreground mb-6">{service.description}</p>
        
        <div className="space-y-3 mb-8">
          {service.features.map((feature, index) => (
            <div key={index} className="flex items-center justify-center space-x-2">
              <CheckCircle className={`h-4 w-4 ${feature.included ? 'text-youtube-red' : 'text-muted'}`} />
              <span className={`text-sm ${feature.included ? 'text-muted-foreground' : 'text-muted line-through'}`}>
                {feature.text}
              </span>
            </div>
          ))}
        </div>
        
        <div className="text-2xl font-bold text-foreground mb-6">{service.price}</div>
        
        <Button 
          className={`w-full font-medium ${
            service.popular 
              ? 'bg-gradient-youtube hover:shadow-glow' 
              : 'variant-outline border-youtube-red text-youtube-red hover:bg-youtube-red hover:text-white'
          } transition-all duration-300`}
        >
          Get Started
        </Button>
      </div>
    </div>
  )
}