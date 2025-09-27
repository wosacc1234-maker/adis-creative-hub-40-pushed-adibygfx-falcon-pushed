import { useState } from "react"
import { Download, FileText, Loader2 } from "lucide-react"
import { Button } from "@/components/ui/button"
import { useToast } from "@/hooks/use-toast"

interface MediaKitGeneratorProps {
  className?: string
}

export function MediaKitGenerator({ className }: MediaKitGeneratorProps) {
  const [isGenerating, setIsGenerating] = useState(false)
  const { toast } = useToast()

  const generateMediaKit = async () => {
    setIsGenerating(true)
    
    try {
      // Simulate PDF generation delay
      await new Promise(resolve => setTimeout(resolve, 2000))
      
      // Create a comprehensive media kit content
      const mediaKitContent = `
      Adil GFX - Professional Media Kit
      
      About:
      Professional designer specializing in logo design, YouTube thumbnails, and video editing.
      Trusted by 500+ clients worldwide with proven results in brand growth and engagement.
      
      Services:
      • Logo Design - Starting at $50
      • YouTube Thumbnails - $25 per thumbnail
      • Video Editing - $100+ per project  
      • YouTube Channel Setup - $200+ package
      
      Portfolio Highlights:
      • TechFlow Startup Logo - Helped secure $5M funding
      • Gaming Channel Thumbnails - 650% CTR improvement
      • SaaS Product Launch Video - $2M first-month revenue
      • MrBeast Style Channel Setup - 500K subscribers in 6 months
      
      Contact Information:
      Email: hello@adilgfx.com
      WhatsApp: Available via website
      Portfolio: https://adilgfx.com/portfolio
      
      Testimonials:
      "Adil's thumbnails increased our CTR from 3% to 12%. ROI was immediate!" - Alex Chen, Gaming Channel
      "The logo design perfectly captured our brand essence. Professional and timely delivery." - Sarah Martinez, TechFlow
      
      Delivery Time: 24-48 hours for most projects
      Revisions: Up to 3 free revisions included
      File Formats: All source files included (PSD, AI, PNG, JPG)
      `
      
      // Create a blob and download
      const blob = new Blob([mediaKitContent], { type: 'text/plain' })
      const url = URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = 'Adil-GFX-Media-Kit.txt'
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      URL.revokeObjectURL(url)
      
      toast({
        title: "Media Kit Downloaded!",
        description: "Your comprehensive media kit has been generated and downloaded."
      })
    } catch (error) {
      toast({
        title: "Download Failed",
        description: "There was an error generating the media kit. Please try again.",
        variant: "destructive"
      })
    } finally {
      setIsGenerating(false)
    }
  }

  return (
    <Button
      onClick={generateMediaKit}
      disabled={isGenerating}
      className={`bg-gradient-youtube hover:shadow-glow transition-all duration-300 ${className}`}
    >
      {isGenerating ? (
        <>
          <Loader2 className="mr-2 h-4 w-4 animate-spin" />
          Generating...
        </>
      ) : (
        <>
          <Download className="mr-2 h-4 w-4" />
          Download Media Kit
        </>
      )}
    </Button>
  )
}